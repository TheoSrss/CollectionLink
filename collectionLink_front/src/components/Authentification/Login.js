import React, {useState} from 'react';
import FormWrapper from "../forms/FormWrapper";
import TextInput from "../forms/TextInput";
import useForm from "../../hooks/useForm";
import emailLogo from "../../assets/svg/email.svg";
import passwordLogo from "../../assets/svg/password.svg";
import logo from "../../assets/svg/logo.svg";
import {Navigate} from "react-router-dom";
import {useAuth} from "../../context/AuthContext";

const Login = () => {
    const {user, loginUser} = useAuth();
    const [loginError, setLoginError] = useState(null);

    const initialState = {username: '', password: ''};

    // const validate = (values) => {
    //     const errors = {};
    //     if (!values.password) errors.password = 'Password is required';
    //     if (!values.username) errors.username = 'Username is required';
    //     return errors;
    // };

    const {values, errors, handleChange, handleSubmit} = useForm(initialState);

    const onSubmit = async (formData) => {
        setLoginError(null);
            try {
            await loginUser(formData.username, formData.password);
        } catch (error) {
            setLoginError('Identifiants invalides ou une erreur s\'est produite lors de la connexion');
        }
    };
    if(user){
        return <Navigate to="/dashbord" replace/>;
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
                {loginError && <div className="text-red-500 mb-4">{loginError}</div>}

                <FormWrapper onSubmit={handleSubmit(onSubmit)}>
                    <TextInput
                        name="username"
                        label="Username"
                        value={values.name}
                        placeholder="satoshin@gmx.com"
                        onChange={handleChange}
                        error={errors.name}
                        logo={emailLogo}
                    />
                    <TextInput
                        isPassword={true}
                        name="password"
                        label="Mot de passe"
                        value={values.password}
                        placeholder="••••••••••"
                        onChange={handleChange}
                        error={errors.password}
                        logo={passwordLogo}
                    />
                    <button type="submit"
                            className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">Connexion
                    </button>
                </FormWrapper>
                <div className="relative flex py-8 items-center">
                    <div className="flex-grow border-t border-[1px] border-gray-200"></div>
                    <span className="flex-shrink mx-4 font-medium text-gray-500">OR</span>
                    <div className="flex-grow border-t border-[1px] border-gray-200"></div>
                </div>
                <form>
                    <div className="flex flex-row gap-2 justify-center">
                        <button className="flex flex-row w-32 gap-2 bg-gray-600 p-2 rounded-md text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                 strokeLinejoin="round" className="lucide lucide-github">
                                <path
                                    d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"></path>
                                <path d="M9 18c-4.51 2-5-2-7-2"></path>
                            </svg>
                            <span className="font-medium mx-auto">Github</span>
                        </button>
                        <button className="flex flex-row w-32 gap-2 bg-gray-600 p-2 rounded-md text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round"
                                 strokeLinejoin="round" className="lucide lucide-twitter">
                                <path
                                    d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                            </svg>
                            <span className="font-medium mx-auto">Twitter</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>);
};

export default Login;