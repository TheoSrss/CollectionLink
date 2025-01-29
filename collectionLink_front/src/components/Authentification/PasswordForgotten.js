import React, {useState} from 'react';
import logo from "../../assets/svg/logo.svg";
import {NavLink} from "react-router-dom";
import {ArrowBigLeft, Mail} from "lucide-react";
import FormWrapper from "../forms/FormWrapper";
import TextInput from "../forms/TextInput";
import useForm from "../../hooks/useForm";
import api from "../../services/api";
import ResetPassword from "./ResetPassword";

const PasswordForgotten = () => {
    const {values, errors, handleChange, handleSubmit, handleApiErrors} = useForm({email: null});
    const [formError, setFormError] = useState(false);
    const [codeIsSend, setCodeIsSend] = useState(false);

    const onSubmit = async (formData) => {
        try {
            const res = await api.post(`forgot-password`, {
                json: {
                    "email": formData.email
                }
            });
            handleApiErrors([]);
            if (res.status === 200) {
                setCodeIsSend(true);
            }
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
    const formToDisplay = () => {
        if (codeIsSend) {
            return <ResetPassword email={values.email} />;
        } else {
            return <FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
                <TextInput
                    name="email"
                    label="Email"
                    value={values.email}
                    placeholder="satoshin@gmx.com"
                    onChange={handleChange}
                    error={errors.email}
                    logo={<Mail/>}
                />
                <button type="submit"
                        className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-6">Envoyer
                    email
                </button>
            </FormWrapper>
        }
    }
    return (<div className="h-screen flex items-center justify-center">
        <div
            className="flex flex-col w-full md:w-1/2 xl:w-2/5 2xl:w-2/5 3xl:w-1/3 mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800">
            <div className="flex flex-row gap-3 pb-4">
                <div>
                    <img src={logo} width="61" alt="Logo"/>
                </div>
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">CollectionLink</h1>
            </div>
            <span>Réinitialisez votre mot de passe en un clic !</span>
            {codeIsSend && (
            <span>Vous venez de recevoir un email avec votre code de validation.</span>)}
            {formToDisplay()}
            <NavLink to="/login">
                <ArrowBigLeft/>
            </NavLink>
        </div>
    </div>);
};

export default PasswordForgotten;