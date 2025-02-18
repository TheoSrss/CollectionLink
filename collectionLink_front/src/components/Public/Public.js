import React, {useEffect, useState} from 'react';
import Loading from "../Loading";
import {NavLink, useParams} from "react-router-dom";
import api from "../../services/api";
import Password from "./Password";
import ThemeToggle from "../ThemeToggle";
import {useAuth} from "../../context/AuthContext";
import PublicCard from "./PublicCard";
import avatar from "../../assets/avatar.png";

const Public = () => {
    const [loading, setLoading] = useState(true);
    const {slug} = useParams();
    const [collection, setCollection] = useState([]);
    const [notFound, setNotFound] = useState(false);
    const [needPassword, setNeedPassword] = useState(false);
    const [password, setPassword] = useState(null);
    const {user} = useAuth();

    useEffect(() => {
        fetchICollection()
    }, [slug, password]);


    const fetchICollection = async () => {
        try {
            const response = await api.get(`collections/public/${slug}${password ? `?password=${password}` : ''}`);
            if (response.ok) {
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
            <div className="flex justify-center ">
                <div
                    className="relative w-52 h-52 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-300 flex flex-col">
                    <img src={avatar} alt="Avatar"/>
                </div>
            </div>
            <h1 className="text-xl font-bold mt-4">@{collection.user.username}</h1>
            <h1 className="text-l font-bold m-4">{collection.user.bio}</h1>
            <p className="text-gray-800 dark:text-gray-300 text-xl">{collection.name}</p>
            <p className="text-gray-500 dark:text-gray-400 text-sm">{collection.description}</p>
        </div>
        <div className="flex flex-wrap justify-center gap-6 mt-12 px-4">
            {collection.collectable.map((c) => (<PublicCard key={c.id} collectable={c}/>))}
        </div>
        {!user && (<NavLink to='/registration'>
            <div className="absolute bottom-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 ">
                <button type="submit"
                        className="relative w-full text-[#FFFFFF] bg-violet-500 dark:bg-violet-400 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    Rejoins {collection.user.username} sur CollectionLink
                </button>
            </div>
        </NavLink>)}
    </div>);

};

export default Public;