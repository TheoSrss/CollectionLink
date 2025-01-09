import React from 'react';

const Table = ({columns, children}) => {
    return (<div className="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-50 dark:bg-gray-900">
        <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mb-3">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                {columns.map((name, index) => (
                    <th key={index} scope="col" className="px-6 py-3 text-center">
                        {name}
                    </th>))}
            </tr>
            </thead>
            <tbody>
            {children}
            </tbody>
        </table>
    </div>);
};
export default Table;