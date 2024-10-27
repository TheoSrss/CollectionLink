import React from 'react';
import ReactDOM from 'react-dom/client';
import './css/index.css';
import App from './App';
import ThemeProvider from "./utils/ThemeContext";
// import './css/satoshi.css';
// import 'jsvectormap/dist/css/jsvectormap.css';
// import 'flatpickr/dist/flatpickr.min.css';


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
    <div className="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 min-h-screen">
        <React.StrictMode>
            <ThemeProvider>
                <App/>
            </ThemeProvider>
        </React.StrictMode>
    </div>
);