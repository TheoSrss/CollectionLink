import React, {useEffect, useState} from 'react';
import FormWrapper from "../forms/FormWrapper";
import TextInput from "../forms/TextInput";
import useForm from "../../hooks/useForm";
import api from "../../services/api";
import Loading from "../Loading";
import {AlignJustify, Mail, User} from "lucide-react";

const Profile = () => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [formError, setFormError] = useState(false);

    // const validate = (values) => {
    //     const errors = {};
    //     return errors;
    // };

    const {values, errors, handleChange, handleSubmit, updateValues, handleApiErrors} = useForm({});

    useEffect(() => {
        if(!user){
            fetchUserData();
        }
    }, [updateValues]);

    const fetchUserData = async () => {
        try {
            const response = await api.get('profile');
            const data = await response.json();
            setUser(data);
            updateValues(data);
            setLoading(false);
        } catch (error) {
            setLoading(false);
        }
    }

    const onSubmit = async (formData) => {
        setFormError(false);
        try {
            const res = await api.patch(`users/${user.id}`, {
                json: {
                    "email": formData.email,
                    "username": formData.username,
                    "bio": formData.bio
                }
            });
            handleApiErrors([]);
        } catch (error) {
            if (error.response && error.response.status === 422) {
                const data = await error.response.json();
                if (data.violations) {
                    handleApiErrors(data.violations);
                }
            } else {
                setFormError(true);
            }
        }
    };

    if (loading) return <Loading></Loading>;

    return (<div className="h-screen flex items-center justify-center">
        <div
            className="flex flex-col w-full md:w-1/2 xl:w-2/5 2xl:w-2/5 3xl:w-1/3 mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 max-w-3xl">
            <div className="flex flex-row gap-3 pb-4">
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mon profil</h1>
            </div>
            <FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
                <TextInput
                    name="email"
                    label="Email"
                    value={values.email}
                    placeholder="satoshin@gmx.com"
                    onChange={handleChange}
                    error={errors.email}
                    logo={<Mail/>}
                />
                <TextInput
                    name="username"
                    label="Username"
                    value={values.username}
                    placeholder="Nom d'utilisateur"
                    onChange={handleChange}
                    error={errors.username}
                    logo={<User/>}
                />
                <TextInput
                    name="bio"
                    label="Biographie"
                    value={values.bio}
                    onChange={handleChange}
                    error={errors.bio}
                    textarea={true}
                    logo={<AlignJustify/>}
                />
                <button type="submit"
                        className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    Mettre à jour
                </button>
            </FormWrapper>
        </div>
    </div>);
};

export default Profile;