import React from 'react';

const Loading = ({ text = 'Chargement...' }) => {
    return (
        <div className="flex flex-col items-center justify-center h-screen">
            <div className="w-12 h-12 border-4 border-blue-400 rounded-full animate-spin border-t-transparent"></div>
            <p className="mt-4 text-lg font-semibold text-gray-700 dark:text-gray-300">{text}</p>
        </div>
    );
};

export default Loading;