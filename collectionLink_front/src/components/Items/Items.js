import React, {useEffect, useState} from 'react';
import api from "../../services/api";
import Loading from "../Loading";
import {NavLink} from "react-router-dom";
import {useAuth} from "../../context/AuthContext";

const Items = () => {
    const [items, setItems] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(false);
    const {user} = useAuth();

    useEffect(() => {
        async function fetchItemsData() {
            try {
                const response = await api.get(`collectables?creator=${user.id}`);
                const data = await response.json();
                setItems(data["member"]);
            } catch (error) {
                setError(true);
            } finally {
                setLoading(false);
            }
        }

        fetchItemsData();
    }, []);

    if (loading) {
        return <Loading></Loading>;
    }

    if (error) {
        return <div className="text-red-500 p-4">Erreur</div>;
    }

    const truncateDescription = (description) => {
        if (!description) return '...';
        return description.length > 50
            ? `${description.slice(0, 50)}...`
            : description;
    };
    return (<div className="h-screen flex items-center justify-center">

        <div
            className="flex flex-col w-full mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 ">
            <div>
                <NavLink to='/profile'>
                    <button
                        className="float-right text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                        Nouvel item
                    </button>
                </NavLink>
            </div>
            <div className="flex flex-row gap-3 pb-4">
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mes items</h1>
            </div>
            <table className="table-auto">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Date de creation</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {items.map(item => (
                    <tr key={item.id}>
                        <td>{item.name}</td>
                        <td>DATE</td>
                        <td>
                            {truncateDescription(item?.description)}
                    </td>
                        <td className='text-center'>
                            <NavLink to='/'>
                                <button
                                    className="text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                                    Modifier
                                </button>
                            </NavLink>
                        </td>

                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    </div>);
};

export default Items;