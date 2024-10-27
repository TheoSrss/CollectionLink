import ky from 'ky';
import {API_BASE_URL, TOKEN_KEY} from '../constants';
import {secureStorage} from '../utils/secureStorage';

const api = ky.create({
    prefixUrl: API_BASE_URL,
    credentials: 'include',
    hooks: {
        beforeRequest: [
            request => {
                const token = secureStorage.getItem(TOKEN_KEY);
                if (token) {
                    request.headers.set('Authorization', `Bearer ${token}`);
                }
                request.headers.set('Content-Type', request.method === 'PATCH' ? 'application/merge-patch+json' : 'application/json');
            }
        ],
        afterResponse: [
            (_request, _options, response) => {
                if (response.status === 401) {
                    secureStorage.removeItem(TOKEN_KEY);
                    window.location = '/login';
                }
            }
        ]
    },
    retry: 0
});

export default api;