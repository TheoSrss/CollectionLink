import React from 'react';

const FormWrapper = ({onSubmit, children, error}) => (
    <form onSubmit={onSubmit}>
        {error && <p className="mt-2 text-sm text-red-600 dark:text-red-500"><span
            className="font-medium"></span>Nous avons rencontré un problème, veuillez réessayer</p>
        }
        {children}
    </form>);

export default FormWrapper;