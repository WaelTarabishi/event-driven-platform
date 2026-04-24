import http from 'k6/http';
import { check } from 'k6';
import { Counter } from 'k6/metrics';

export const options = {
  scenarios: {
    burst: {
      executor: 'per-vu-iterations',
      vus: 50,
      iterations: 1,
      maxDuration: '2m',
    },
  },
};

const successBookings = new Counter('success_bookings');
const failedBookings = new Counter('failed_bookings');
const conflictBookings = new Counter('conflict_bookings');
const authFailures = new Counter('auth_failures');
const bookingStatus302 = new Counter('booking_status_302');
const bookingStatus419 = new Counter('booking_status_419');
const bookingOtherStatus = new Counter('booking_other_status');

const BASE_URL = __ENV.BASE_URL || 'http://127.0.0.1:8000';
const EVENT_ID = Number(__ENV.EVENT_ID || 1);
const PASSWORD = __ENV.LOAD_PASSWORD || 'password';

function extractCsrfToken(html) {
  const match = html.match(/name="csrf-token" content="([^"]+)"/);
  return match ? match[1] : '';
}

export default function () {
  const vu = __VU;
  const email = `load${vu}@test.com`;
  const jar = http.cookieJar();

  const loginPageRes = http.get(`${BASE_URL}/login`, { redirects: 2, jar });
  const csrfToken = extractCsrfToken(loginPageRes.body || '');

  const loginRes = http.post(
    `${BASE_URL}/login`,
    {
      email,
      password: PASSWORD,
      _token: csrfToken,
    },
    {
      redirects: 0,
      headers: {
        Accept: 'text/html,application/xhtml+xml',
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      jar,
    },
  );

  const location = (loginRes.headers.Location || loginRes.headers.location || '').toString();
  const authenticated = (loginRes.status === 302 || loginRes.status === 303) && !location.includes('/login');
  if (!authenticated) {
    if (__VU <= 3) {
      console.log(`VU ${__VU} login failed: status=${loginRes.status} location=${location}`);
    }
    authFailures.add(1);
    failedBookings.add(1);
    return;
  }

  const dashboardRes = http.get(`${BASE_URL}/dashboard`, {
    redirects: 0,
    jar,
  });

  const dashboardLocation = (dashboardRes.headers.Location || dashboardRes.headers.location || '').toString();
  if (dashboardRes.status === 302 && dashboardLocation.includes('/login')) {
    if (__VU <= 3) {
      console.log(`VU ${__VU} not authenticated after login.`);
    }
    authFailures.add(1);
    failedBookings.add(1);
    return;
  }

  const dashboardCsrf = extractCsrfToken(dashboardRes.body || '');
  const xsrfTokenCookie = jar.cookiesForURL(`${BASE_URL}/`)['XSRF-TOKEN']?.[0] || '';
  const xsrfToken = dashboardCsrf || (xsrfTokenCookie ? decodeURIComponent(xsrfTokenCookie) : csrfToken);

  const payload = JSON.stringify({
    event_id: EVENT_ID,
    payment_method: 'fake_card',
    payment_token: 'tok_demo_success',
  });

  const res = http.post(`${BASE_URL}/api/bookings`, payload, {
    redirects: 0,
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-CSRF-TOKEN': xsrfToken,
      'X-XSRF-TOKEN': xsrfToken,
      'X-Requested-With': 'XMLHttpRequest',
      Referer: `${BASE_URL}/dashboard`,
    },
    jar,
  });

  if (res.status === 201) successBookings.add(1);
  else {
    failedBookings.add(1);
    if (res.status === 409 || res.status === 422) {
      conflictBookings.add(1);
    } else if (res.status === 302) {
      bookingStatus302.add(1);
    } else if (res.status === 419) {
      bookingStatus419.add(1);
    } else {
      bookingOtherStatus.add(1);
      if (__VU <= 3) {
        console.log(`VU ${__VU} booking failed status=${res.status} body=${String(res.body).slice(0, 180)}`);
      }
    }
  }

  check(res, {
    'status is 201 or known failure': (r) => [201, 409, 422].includes(r.status),
  });
}
