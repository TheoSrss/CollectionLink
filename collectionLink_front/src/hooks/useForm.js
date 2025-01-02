import {useState} from 'react';

const useForm = (initialState, validate) => {
    const [values, setValues] = useState(initialState);
    const [errors, setErrors] = useState({});
    const updateValues = (newValues) => {
        setValues(newValues);
    };
    const handleChange = (e) => {
        const {name, value} = e.target;
        setValues({...values, [name]: value});
    };

    const handleSubmit = (onSubmit) => (e) => {
        e.preventDefault();
        const validationErrors = validate(values);
        setErrors(validationErrors);
        onSubmit(values);
    };
    return {values, errors, handleChange, handleSubmit, setValues,updateValues};
};

export default useForm;