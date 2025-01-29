import React, {createContext, useCallback, useContext, useEffect, useReducer} from 'react';
import {isAuthenticated, login as authLogin, logout as authLogout} from '../services/auth';
import api from "../services/api";
import {secureStorage} from "../utils/secureStorage";
import {TOKEN_KEY} from "../constants";

const AuthContext = createContext(null);

// Reducer pour gérer les états d'authentification
const authReducer = (state, action) => {
    switch (action.type) {
        case 'AUTH_CHECK_START':
            return {...state, loading: true};
        case 'AUTH_CHECK_COMPLETE':
            return {
                loading: false, authenticated: action.payload.authenticated, user: action.payload.user
            };
        case 'SET_USER':
            return {
                ...state, loading: false, authenticated: true, user: action.payload
            };
        case 'LOGOUT':
            return {
                loading: false, authenticated: false, user: null
            };
        default:
            return state;
    }
};

export const AuthProvider = ({children}) => {
    const [state, dispatch] = useReducer(authReducer, {
        loading: true, authenticated: false, user: null
    });

    // Vérification de l'authentification
    const checkAuth = useCallback(async () => {
        dispatch({type: 'AUTH_CHECK_START'});
        const authenticated = isAuthenticated();

        if (authenticated && !state.user) {
            try {
                const profileResponse = await api.get('profile').json();
                dispatch({
                    type: 'AUTH_CHECK_COMPLETE', payload: {
                        authenticated: true, user: profileResponse
                    }
                });
            } catch (error) {
                dispatch({
                    type: 'AUTH_CHECK_COMPLETE', payload: {
                        authenticated: false, user: null
                    }
                });
                authLogout();
            }
        } else {
            dispatch({
                type: 'AUTH_CHECK_COMPLETE', payload: {
                    authenticated, user: state.user
                }
            });
        }
    }, [state.user]);

    // Vérification périodique
    useEffect(() => {
        checkAuth();
        const interval = setInterval(checkAuth, 5000);
        return () => clearInterval(interval);
    }, [checkAuth]);

    const loginUser = async (username, password) => {
        const response = await authLogin(username, password);
        if (response.token) {
            await updateUser();
        }
    };

    const logoutUser = () => {
        authLogout();
        dispatch({type: 'LOGOUT'});
    };

    const updateUser = async () => {
        const profileResponse = await api.get('profile').json();
        dispatch({
            type: 'SET_USER', payload: {...profileResponse, token: secureStorage.getItem(TOKEN_KEY)}
        });
    }

    return (<AuthContext.Provider
        value={{
            user: state.user,
            loading: state.loading,
            authenticated: state.authenticated,
            loginUser,
            logoutUser,
            updateUser,
        }}
    >
        {children}
    </AuthContext.Provider>);
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};