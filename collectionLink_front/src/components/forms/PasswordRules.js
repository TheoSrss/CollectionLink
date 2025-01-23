import React, {useEffect, useState} from "react";

const PasswordRules = ({password}) => {
    const rules = [{id: 1, message: "8 caractères", validate: (password) => password.length >= 8}, {
        id: 2, message: "Lettre minuscule", validate: (password) => /[a-z]/.test(password)
    }, {id: 3, message: "Lettre majuscule", validate: (password) => /[A-Z]/.test(password)}, {
        id: 4, message: "Chiffre", validate: (password) => /\d/.test(password)
    }, {id: 5, message: "Caractère spécial (@ $  ! % * ? &)", validate: (password) => /[!@#?]/.test(password)},];

    const [validationStatus, setValidationStatus] = useState(rules.map((rule) => ({id: rule.id, valid: false})));

    useEffect(() => {
        const newStatus = rules.map((rule) => ({
            id: rule.id, valid: rule.validate(password || ""),
        }));
        setValidationStatus(newStatus);
    }, [password]);

    return (<ul className="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400 mt-2 mt-0">
        {rules.map((rule) => {
            const isValid = validationStatus.find((status) => status.id === rule.id)?.valid;
            return (<li key={rule.id}
                        className={`flex items-center ${isValid ? "text-green-700 " : "text-gray-500 dark:text-gray-300"}`}>
                <svg
                    className={`w-3.5 h-3.5 me-2 ${isValid ? "text-green-700 " : "text-gray-300 dark:text-gray-300"} flex-shrink-0`}
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                {rule.message}
            </li>);
        })}
    </ul>);
};

export default PasswordRules;
