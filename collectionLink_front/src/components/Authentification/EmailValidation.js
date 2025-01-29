import React, {useState} from 'react';
import FormWrapper from "../forms/FormWrapper";
import useForm from "../../hooks/useForm";
import TextInput from "../forms/TextInput";
import {TicketCheck} from "lucide-react";
import api from "../../services/api";
import {useAuth} from "../../context/AuthContext";

const EmailValidation = ({email}) => {
    const initialState = {code: ''};
    const [formError, setFormError] = useState(false);
    const {values, errors, handleChange, handleSubmit, handleApiErrors} = useForm(initialState);
    const {updateUser} = useAuth();

    const onSubmit = async (formData) => {
        try {
            setFormError(false);
            const res = await api.post(`verify-email`, {
                json: {
                    "code": formData.code,
                }
            });
            handleApiErrors([]);
            if (res.status === 200) {
                updateUser();
            }
        } catch (error) {
            if (error.response && error.response.status === 400) {
                handleApiErrors([{'propertyPath': 'code', 'code': '3c4d5e6f-7g8h-9i10-j11k-12l13m14n162'}]);
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
        <button type="submit"
                className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-6">Valider
        </button>
    </FormWrapper>);
};

export default EmailValidation;