import React, {useState} from 'react';

const MultipleSelectInput = ({label, name, options, itemTitle, selectedValues, onChange}) => {
    const [isOpen, setIsOpen] = useState(false);
    const toggleDropdown = () => setIsOpen(!isOpen);
    const handleSelect = (option) => {
        if (selectedValues.includes(option)) {
            onChange(selectedValues.filter((value) => value !== option));
        } else {
            onChange([...selectedValues, option]);
        }
    };

    return (<div className="my-6">
        {label && (<label htmlFor={name} className="mb-2.5 block font-medium text-black dark:text-gray-100">
            {label}
        </label>)}
        <div className="relative text-gray-400">

            <button
                id={name}
                type="button"
                onClick={toggleDropdown}
                className="w-full py-3 border border-stroke rounded-lg bg-transparent focus:outline-none focus:ring-2 focus:ring-border-primary outline-none  text-black  dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary dark:text-gray-100 outline-nonefocus-visible:shadow-none dark:border-form-strokedark "
            >
                {selectedValues.length > 0 ? selectedValues.map(item => item[itemTitle]).join(', ') : 'Sélectionnez...'}
            </button>
            {isOpen && (<div
                className="absolute  w-full bg-white border mt-2 rounded-lg shadow-lg max-h-60 overflow-y-auto dark:bg-gray-800">
                {options.map((option) => (<div
                    key={option['@id']}
                    onClick={() => handleSelect(option)}
                    className={`cursor-pointer px-4 py-2 hover:bg-indigo-50 dark:hover:bg-gray-600 ${selectedValues.some(value => value['@id'] === option['@id']) ? 'bg-indigo-100 dark:bg-gray-700 font-bold' : ''}`}
                >
                    {option[itemTitle]}
                </div>))}
            </div>)}
        </div>
    </div>);
};

export default MultipleSelectInput;