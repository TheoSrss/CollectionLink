import React, {useEffect, useState} from 'react';
import TextInput from "../forms/TextInput";
import FormWrapper from "../forms/FormWrapper";
import useForm from "../../hooks/useForm";
import {KeySquare} from 'lucide-react';

const Password = ({password, setPassword,error}) => {
    const [localPassword, setLocalPassword] = useState(password || "");
    const {handleChange, handleSubmit, values, setValues, handleApiErrors, errors} = useForm({localPassword});
    const [formError, setFormError] = useState(false);
    useEffect(() => {
        if (error){
            handleApiErrors([{'propertyPath':'password','code': '3c4d5e6f-7g8h-9i10-j11k-12l13m14n162'}]);
        }
    }, [error]);
    const onSubmit = () => {
        setPassword(values.password);
    };
    return (

        <div className="h-screen flex items-center justify-center">
            <div
                className="flex flex-col w-full md:w-1/2 xl:w-2/5 2xl:w-2/5 3xl:w-1/3 mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 max-w-3xl">
                <div className="flex flex-row gap-3 pb-4">
                    <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Collection en privée</h1>
                </div>
                <FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
                    <TextInput
                        name="password"
                        label="Mot de passe"
                        value={values.password}
                        onChange={handleChange}
                        logo={<KeySquare/>}
                        error={errors.password}
                    />
                    <button type="submit"
                            className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                        Accéder a la collection
                    </button>
                </FormWrapper>
            </div>
        </div>


    );
};

export default Password;