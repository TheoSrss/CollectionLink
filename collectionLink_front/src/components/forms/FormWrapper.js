import React from 'react';

const FormWrapper = ({ onSubmit, children }) => (
    <form onSubmit={onSubmit}>
        {children}
    </form>
);

export default FormWrapper;