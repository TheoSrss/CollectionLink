import CryptoJS from 'crypto-js';

const SECRET_KEY = process.env.REACT_APP_SECRET_KEY

export const secureStorage = {
    setItem: (key, value) => {
        const encrypted = CryptoJS.AES.encrypt(JSON.stringify(value), SECRET_KEY).toString();
        localStorage.setItem(key, encrypted);
    },
    getItem: (key) => {
        const encrypted = localStorage.getItem(key);
        if (encrypted) {
            const decrypted = CryptoJS.AES.decrypt(encrypted, SECRET_KEY).toString(CryptoJS.enc.Utf8);
            return JSON.parse(decrypted);
        }
        return null;
    },
    removeItem: (key) => {
        localStorage.removeItem(key);
    }
};