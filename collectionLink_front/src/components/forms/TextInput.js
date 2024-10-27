import defaultLogo from "../../assets/svg/defaultFormSvg/TextInput.svg"

const TextInput = ({name, label, value, placeholder, onChange, error, logo, isPassword = false}) => (<div className="my-6">
    <label className="mb-2.5 block font-medium text-black dark:text-gray-100" htmlFor={name}>{label}</label>
    <div className="relative text-gray-400">
            <span
                className="absolute inset-y-0 left-0 flex items-center p-1 pl-3 ">
                <img src={logo ? logo : defaultLogo} className="max-h-6"/>
        </span>
        <input
            type={isPassword ? "password" : "text"}
            id={name}
            name={name}
            value={value}
            onChange={onChange}
            placeholder={placeholder}
            className="w-full rounded-lg border border-stroke bg-transparent py-4 pl-12 pr-10 text-black outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary dark:text-gray-100"
        />
    </div>
    {error && <span className="error-message">{error}</span>}
</div>);
export default TextInput;