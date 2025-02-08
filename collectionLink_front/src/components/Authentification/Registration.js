import React, {useState} from 'react';
import FormWrapper from "../forms/FormWrapper";
import useForm from "../../hooks/useForm";
import logo from "../../assets/svg/logo.png";
import {NavLink} from "react-router-dom";
import {useAuth} from "../../context/AuthContext";
import TextInput from "../forms/TextInput";
import api from "../../services/api";
import {KeySquare, Mail, User} from 'lucide-react';
import EmailValidation from "./EmailValidation";

const Registration = () => {
    const {user, loginUser} = useAuth();
    const [formError, setFormError] = useState(false);
    const [codeIsSend, setCodeIsSend] = useState(true);
    const initialState = {username: '', email: '', password: ''};
    const {values, errors, handleChange, handleSubmit, handleApiErrors} = useForm(initialState);

    const onSubmit = async (formData) => {
        try {
            const res = await api.post(`register`, {
                json: {
                    "email": formData.email, "password": formData.password, "username": formData.username
                }
            });
            handleApiErrors([]);
            if (res.status === 201) {
                setFormError(true);
                await loginUser(formData.username, formData.password);
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
        if (codeIsSend && user) {
            return <EmailValidation email={user.email}/>;
        }
        return <FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
            <TextInput
                name="username"
                label="Username"
                value={values.username}
                placeholder="satoshin"
                onChange={handleChange}
                error={errors.username}
                logo={<User/>}
            />
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
                isPassword={true}
                name="password"
                label="Mot de passe"
                value={values.password}
                placeholder="••••••••••"
                onChange={handleChange}
                error={errors.password}
                isPasswordEditing={true}
                logo={<KeySquare/>}
            />
            <button type="submit"
                    className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-6 mb-2">Inscription
            </button>
        </FormWrapper>

    }
    return (<div className="h-screen flex items-center justify-center">
        <div
            className="flex flex-col w-full md:w-1/2 xl:w-2/5 2xl:w-2/5 3xl:w-1/3 mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800">
            <div className="flex flex-row gap-3 pb-4">
                <div>
                    <img src={logo} width="61" alt="Logo"/>
                </div>
                <h1 className="text-3xl font-bold dark:text-gray-300  my-auto"><span
                    className='text-gray-400 dark:text-gray-500'> CollectionLink | </span>{codeIsSend ? 'Validation' : 'Inscription'}
                </h1>
            </div>
            {formToDisplay()}
            <div className='text-center'>
                <NavLink to="/login">
                    <span className="text-sm underline cursor-pointer m-auto">J'ai déjà un compte</span>
                </NavLink>
            </div>
        </div>
    </div>);
};

export default Registration;