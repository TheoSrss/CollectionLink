import defaultLogo from "../../assets/svg/defaultFormSvg/TextInput.svg"
import {getErrorMessage} from '../../constants';
import PasswordRules from "./PasswordRules";

const TextInput = ({
                       name,
                       label,
                       value,
                       placeholder,
                       onChange,
                       error,
                       logo,
                       isPassword = false,
                       isPasswordEditing = false,
                       textarea = false,
                       isNumber = false
                   }) => {
    const errorMessage = getErrorMessage(error);


    return (<div className="my-6">
        <label
            className={`mb-2.5 block font-medium ${error ? 'text-red-700 dark:text-red-500' : 'text-black dark:text-gray-100'}`}
            htmlFor={name}>
            {label}
        </label>
        <div className="relative text-gray-400 mb-2">
            <span
                className="absolute inset-y-0 left-0 flex items-center p-1 pl-3 ">
                <img src={logo ? logo : defaultLogo} className="max-h-6"/>
        </span>
            {textarea ? <textarea
                id={name}
                name={name}
                value={value != null ? value : ''}
                onChange={onChange}
                placeholder={placeholder}
                className={`w-full rounded-lg border border-stroke bg-transparent py-4 pl-12 pr-10 text-black outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary dark:text-gray-100 ${error ? 'border-red-500' : ''}`}
            /> : <input
                type={isPassword ? "password" : isNumber ? "number" : "text"}
                id={name}
                name={name}
                value={value != null ? value : ''}
                onChange={onChange}
                placeholder={placeholder}
                className={`w-full rounded-lg border border-stroke bg-transparent py-4 pl-12 pr-10 text-black outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary dark:text-gray-100 ${error ? 'border-red-500' : ''}`}
            />}
        </div>
        {error && <span className="text-sm text-red-600 dark:text-red-500">{errorMessage}</span>}
        {isPasswordEditing && <PasswordRules password={value || ""}/>}
    </div>)
};
export default TextInput;