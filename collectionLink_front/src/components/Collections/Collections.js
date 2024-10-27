import React, {useEffect, useState} from 'react';
import api from "../../services/api";
import Loading from "../Loading";
import {NavLink} from "react-router-dom";

const Collections = () => {
    const [collections, setCollections] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        async function fetchCollectionsData() {
            try {
                const response = await api.get('collections');
                const data = await response.json();
                setCollections(data["hydra:member"]);
                console.log(collections)

                // setValues(data);
                setLoading(false);
            } catch (error) {
                console.log('errreur')
                // setLoading(false);
            }
            console.log(collections)
        }
        fetchCollectionsData();
    }, []);

    if (loading){
     return <Loading></Loading>;
    }

    return (<div className="h-screen flex items-center justify-center">

        <div
            className="flex flex-col w-full mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 ">
            <NavLink to='/profile'>
                <button
                    className="float-right text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    Nouvelle collection
                </button>
            </NavLink>
            <div className="flex flex-row gap-3 pb-4">
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mes collections</h1>
            </div>


            <table className="table-auto">
                <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de creation</th>
                    <th>Nombre d'items</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {collections.map(collection => (
                    <tr>
                        <td>{collection.name}</td>
                        <td>DATE</td>
                        <td>{collection.collectable.length}</td>
                        <td  className='text-center'>
                            <NavLink to='/profile'>
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

export default Collections;