import { useReducer, useEffect, useCallback } from 'react';
import { isAuthenticated } from '../services/auth';

const authReducer = (state, action) => {
    switch (action.type) {
        case 'AUTH_CHECK_START':
            return { ...state, loading: true };
        case 'AUTH_CHECK_COMPLETE':
            return { loading: false, authenticated: action.payload };
        default:
            return state;
    }
};

export const useAuth = () => {
    const [state, dispatch] = useReducer(authReducer, {
        loading: true,
        authenticated: true
    });

    const checkAuth = useCallback(() => {
        dispatch({ type: 'AUTH_CHECK_START' });
        const auth = isAuthenticated();
        // console.log("isAuthenticated() returned:", auth);
        dispatch({ type: 'AUTH_CHECK_COMPLETE', payload: auth });
    }, []);

    useEffect(() => {
        checkAuth(); // Vérification initiale

        const interval = setInterval(checkAuth, 5000);
        return () => clearInterval(interval);
    }, [checkAuth]);

    useEffect(() => {
        // console.log("Auth state updated:", state);
    }, [state]);

    return state;
};