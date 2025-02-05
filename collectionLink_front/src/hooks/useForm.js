import {useState} from 'react';

const useForm = (initialState) => {
    const [values, setValues] = useState(initialState);
    const [errors, setErrors] = useState({});


    const updateValues = (newValues) => {
        setValues(newValues);
    };
    const handleChange = (e) => {
        const { name, value, type, checked, files } = e.target;
        if (Array.isArray(values[name])) {
            setValues({ ...values, [name]: files ? Array.from(files) : [] });
        } else {
            setValues({ ...values, [name]: type === "checkbox" ? checked : value });
        }
    };

    const handleSubmit = (onSubmit) => (e) => {
        e.preventDefault();
        onSubmit(values);
    };

    const handleApiErrors = (violations) => {
        const newErrors = {};
        violations.forEach((violation) => {
            newErrors[violation.propertyPath] = violation.code;
        });
        setErrors(newErrors);
    };

    return {values, errors, handleChange, handleSubmit, setValues, updateValues, handleApiErrors};
};

export default useForm;