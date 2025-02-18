import {useState} from 'react';

const useForm = (initialState) => {
    const [values, setValues] = useState(initialState);
    const [defaultValues, setDefaultValues] = useState(initialState);
    const [errors, setErrors] = useState({});

    const updateValues = (newValues) => {
        setValues(newValues);
        setDefaultValues(newValues);
    };
    const handleChange = (e) => {
        if (!e.target) {
            // @TODO ajouter cette logique pour  les collectables dans le form collections
            setValues(prev => ({ ...prev, [e.name]: e.value }));
        } else {
            const { name, value, type, checked } = e.target;
            setValues(prev => ({ ...prev, [name]: type === "checkbox" ? checked : value }));
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

    return {values, errors, handleChange, handleSubmit, setValues, updateValues, handleApiErrors,defaultValues};
};

export default useForm;