import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../services/api';
import { useAuth } from '../../context/AuthContext';
// import './Dashboard.css';

const Dashboard = () => {
    const {user, loginUser} = useAuth()
    // console.log('ICICICI')
    // const [data, setData] = useState(null);
    // const navigate = useNavigate();
    // const { user, logoutUser } = useAuth();

    // useEffect(() => {
    //     const fetchData = async () => {
    //         try {
    //             const response = await api.get('/dashboard');
    //             setData(response.data);
    //         } catch (error) {
    //             console.error('Failed to fetch data', error);
    //             navigate('/login');
    //         }
    //     };
    //     fetchData();
    // }, [navigate]);
    //
    // const handleLogout = () => {
    //     logoutUser();
    //     navigate('/login');
    // };

    return (
        <div className="flex h-screen overflow-hidden ">
            DASHBOARD
        </div>
    );
};

export default Dashboard;