import http from 'k6/http';

export const options = {
  scenarios: {
    constant_request_rate: {
      executor: 'constant-arrival-rate',
      rate: 50, // 1000 request
      timeUnit: '1m', // per menit
      duration: '1m',
      preAllocatedVUs: 50,
      maxVUs: 200,
    },
  },
};

export default function () {
  http.get('https://devtes.telusur.co.id/');
}
