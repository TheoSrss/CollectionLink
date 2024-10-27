import api from './api';
import { TOKEN_KEY } from '../constants';
import { secureStorage } from '../utils/secureStorage';

export const login = async (username, password) => {
    const response = await api.post('login', { json: { username, password } }).json();
    secureStorage.setItem(TOKEN_KEY, response.token);
    return response;
};

export const logout = () => {
    secureStorage.removeItem(TOKEN_KEY);
};

export const isAuthenticated = () => {
    return !!secureStorage.getItem(TOKEN_KEY);
};