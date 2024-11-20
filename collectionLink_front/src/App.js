import React, {useState} from 'react';
import {BrowserRouter as Router, Navigate, Outlet, Route, Routes} from 'react-router-dom';
import {AuthProvider, useAuth} from './context/AuthContext';
// import {useAuth} from './hooks/useAuth';
import Login from './components/Authentification/Login';
import Dashboard from "./components/Dashboard/Dashboard";
import Sidebar from "./partiels/Sidebar";
import Header from "./partiels/Header";
import './css/index.css';
import './css/satoshi.css';
import Profile from "./components/Profile/Profile";
import Collections from "./components/Collections/Collections";
import Items from "./components/Items/Items";

const PrivateLayout = ({sidebarOpen, setSidebarOpen}) => (
    <div className="flex h-screen overflow-hidden">
        <Sidebar sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen}/>
        <div className="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <Header sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen}/>
            <main className="grow p-10">
                <Outlet/>
            </main>
        </div>
    </div>
);

const PrivateRoute = ({children}) => {
    const {loading, authenticated} = useAuth();
    if (loading) {
        return <div>Chargement...</div>;
    }

    return authenticated ? children : <Navigate to="/login" replace/>;
};

function App() {
    const [sidebarOpen, setSidebarOpen] = useState(false);

    return (
        <AuthProvider>
            <Router>
                <Routes>
                    <Route path="/login" element={<Login/>}/>

                    <Route path="/" element={
                        <PrivateRoute>
                            <PrivateLayout sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen}/>
                        </PrivateRoute>
                    }>
                        <Route index element={<Navigate to="/dashboard" replace />} />
                        <Route path="dashboard" element={<Dashboard />}/>
                        <Route path="profile" element={<Profile/>}/>
                        <Route path="collections" element={<Collections/>}/>
                        <Route path="items" element={<Items/>}/>
                    </Route>

                    <Route path="*" element={<Navigate to="/" replace/>}/>
                </Routes>
            </Router>
        </AuthProvider>
    );
}

export default App;