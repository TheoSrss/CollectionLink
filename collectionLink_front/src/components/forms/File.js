import {useEffect, useState} from "react";
import {Label} from "flowbite-react";
import {getErrorMessage} from "../../constants";

const File = ({name, label, error, onChange, value, max = 1}) => {
    const [previews, setPreviews] = useState([]);
    const errorMessage = getErrorMessage(error);

    useEffect(() => {
        if (value && Array.isArray(value)) {
            setPreviews(value);
        }
    }, [value]);

    const handleFileChange = (event) => {
        const files = Array.from(event.target.files);
        const limit = max - previews.length;
        const newFiles = files.slice(0, limit);
        const newPreviews = newFiles.map(file => ({
            url: URL.createObjectURL(file), file: file
        }));
        setPreviews(prev => {
            const updatedPreviews = [...prev, ...newPreviews];
            onChange({name, value: updatedPreviews}); // Met à jour avec la bonne valeur
            return updatedPreviews;
        });
    };

    const removePreview = (indexToRemove) => {
        const newPreviews = previews.filter((_, index) => index !== indexToRemove);
        setPreviews(newPreviews);
        onChange({name: name, value: newPreviews});
    };

    return (<div className="my-6">
        <label
            className={`mb-2.5 block font-medium ${error ? "text-red-700 dark:text-red-500" : "text-black dark:text-gray-100"}`}
            htmlFor={name}
        >
            {label}
        </label>
        <div className="flex w-full items-center justify-center">
            <Label
                htmlFor={name}
                className={`flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed ${error ? "border-red-500" : "border-gray-300"} bg-gray-50 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600`}
            >
                <div className="flex flex-col items-center justify-center pb-6 pt-5">
                    <svg
                        className="mb-4 h-8 w-8 text-gray-500 dark:text-gray-400"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 20 16"
                    >
                        <path
                            stroke="currentColor"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                        />
                    </svg>
                    <p className="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span className="font-semibold">Cliquez pour télécharger</span>
                    </p>
                    <p className="text-xs text-gray-500 dark:text-gray-400">Formats acceptés : JPEG, JPG, WEBP et
                        PNG</p>
                </div>
                <input id={name} type="file" className="hidden" multiple onChange={handleFileChange}/>
            </Label>
        </div>
        {error && <span className="text-sm text-red-600 dark:text-red-500">{errorMessage}</span>}
        <div className="mt-4 flex flex-wrap gap-2">
            {previews.map((preview, index) => (<div key={index} className="relative">
                    <img
                        src={preview.url}
                        alt={`preview-${index}`}
                        className="w-32 h-32 object-cover rounded-lg shadow"
                    />
                    <button
                        type="button"
                        onClick={() => removePreview(index)}
                        className="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
                    >
                        ×
                    </button>
                </div>

            ))}
        </div>
    </div>);
};

export default File;
