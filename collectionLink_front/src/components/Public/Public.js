import React, {useEffect, useState} from 'react';
import Loading from "../Loading";
import {NavLink, useParams} from "react-router-dom";
import api from "../../services/api";
import Password from "./Password";
import ThemeToggle from "../ThemeToggle";

const Public = () => {
    const [loading, setLoading] = useState(true);
    const {slug} = useParams();
    const [collection, setCollection] = useState([]);
    const [notFound, setNotFound] = useState(false);
    const [needPassword, setNeedPassword] = useState(false);
    const [password, setPassword] = useState(null);


    useEffect(() => {
        fetchICollection()
    }, [slug, password]);


    const fetchICollection = async () => {
        console.log("Fetching collection...", password);
        try {
            const response = await api.get(`collections/public/${slug}${password ? `?password=${password}` : ''}`);
            if (response.ok) {
                console.log('ok')
                const data = await response.json();
                setCollection(data);
                setNeedPassword(false);
            }

        } catch (error) {
            if (error.response && error.response.status === 404) {
                setNotFound(true);
            } else if (error.response && error.response.status === 403) {
                setNeedPassword(true)
            }
        } finally {
            setLoading(false);
        }
    };

    if (loading) return <Loading/>;
    if (notFound) return 'Not Found';
    if (needPassword) return <Password password={password} setPassword={setPassword} error={!!password}/>;

    return (<div className="flex flex-col ustify-center min-h-screen bg-gray-200 dark:bg-gray-800">
        <div className="absolute top-5 right-5">
            <ThemeToggle/>
        </div>
        <div className="text-center mt-16">
            {/* Profile Image */}
            {/*/!*<div className="w-24 h-24 mx-auto rounded-full overflow-hidden">*!/*/}
            {/*<div*/}
            {/*    className="flex justify-center relative w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">*/}
            {/*    <svg className="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor"*/}
            {/*         viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">*/}
            {/*        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"*/}
            {/*              clip-rule="evenodd"></path>*/}
            {/*    </svg>*/}
            {/*    /!*</div>*!/*/}
            {/*</div>*/}
            <div className="flex justify-center ">
                <div className="relative w-52 h-52 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                    {/*<svg className="absolute w-30 h-24 text-gray-400 -left-1" fill="currentColor"*/}
                    {/*     viewBox="0 0 20 20"*/}
                    {/*     xmlns="http://www.w3.org/2000/svg">*/}
                    {/*    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"*/}
                    {/*          clip-rule="evenodd"></path>*/}
                    {/*</svg>*/}
                </div>
            </div>

            <h1 className="text-xl font-bold mt-4">@{collection.user.username}</h1>
            <p className="text-gray-500 text-sm">DESCRIPTION DE MON COMPTE</p>
        </div>
        <NavLink to='/register'>
            <div className="absolute bottom-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 ">
                <button type="submit"
                        className="relative w-full text-[#FFFFFF] bg-violet-500 dark:bg-violet-400 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    Rejoins {collection.user.username} sur CollectionLinksur CollectionLinksur CollectionLinksur
                    CollectionLink
                </button>
            </div>
        </NavLink>
    </div>);

};

export default Public;