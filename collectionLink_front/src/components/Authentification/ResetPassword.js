import React, {useState} from 'react';
import FormWrapper from "../forms/FormWrapper";
import TextInput from "../forms/TextInput";
import useForm from "../../hooks/useForm";
import api from "../../services/api";
import {useNavigate} from "react-router-dom";
import {KeySquare, TicketCheck} from "lucide-react";

const ResetPassword = ({email}) => {

    const {values, errors, handleChange, handleSubmit, handleApiErrors} = useForm({code: null, password: null});
    const [formError, setFormError] = useState(false);
    const navigate = useNavigate();

    const onSubmit = async (formData) => {
        try {
            const res = await api.post(`reset-password`, {
                json: {
                    "email": email, "code": parseInt(formData.code), "password": formData.password,
                }
            });
            handleApiErrors([]);
            if (res.status === 200) {
                navigate('/login')
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
    return (<FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
        <TextInput
            name="code"
            label="Code de validation"
            value={values.code}
            onChange={handleChange}
            error={errors.code}
            isNumber={true}
            logo={<TicketCheck/>}
        />
        <TextInput
            name="password"
            label="Nouveau mot de passe"
            value={values.password}
            onChange={handleChange}
            error={errors.password}
            isPassword={true}
            isPasswordEditing={true}
            logo={<KeySquare/>}
        />
        <button type="submit"
                className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-6">Valider
        </button>
    </FormWrapper>);
};

export default ResetPassword;