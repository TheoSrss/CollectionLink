import React from "react";
import {ChevronDown, ChevronLeft, ChevronRight, ChevronUp} from "lucide-react";

const Table = ({
                   columns,
                   handleSort,
                   sortColumn,
                   sortOrder,
                   children,
                   totalItem = 5,
                   currentPage = 1,
                   handleCurrentPage
               }) => {

    const totalPages = Math.max(1, Math.ceil(totalItem / 5));

    return (<div className="relative overflow-x-auto shadow-md sm:rounded-lg bg-gray-50 dark:bg-gray-900">
        <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-3">
            <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                {columns.map(({key, label, sortable}, index) => (<th
                    key={index}
                    scope="col"
                    className="px-6 py-3 text-center cursor-pointer select-none"
                    onClick={() => sortable && handleSort(key)}
                >
                    <div className="flex justify-center items-center">
                        {label}
                        {sortable && sortColumn === key && (sortOrder === "asc" ? <ChevronUp size={16}/> :
                            <ChevronDown size={16}/>)}
                    </div>
                </th>))}
            </tr>
            </thead>
            <tbody>
            {children}
            </tbody>
        </table>
        {/* Pagination Controls */}
        {<div className="flex justify-end items-center p-4">
            <div className="flex items-center">
                <button
                    onClick={() => handleCurrentPage(currentPage - 1)}
                    disabled={currentPage === 1}
                    className="px-3 py-1 mr-3 border rounded disabled:opacity-50"
                >
                    <ChevronLeft size={16}/>
                </button>
                <span>
                             {currentPage} / {totalPages}
                </span>
                <button
                    onClick={() => handleCurrentPage(currentPage + 1)}
                    disabled={currentPage === totalPages}
                    className="px-3 py-1 ml-3 border rounded disabled:opacity-50"
                >
                    <ChevronRight size={16}/>
                </button>
            </div>
        </div>}
    </div>);
};

export default Table;
