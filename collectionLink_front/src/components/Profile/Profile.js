import React, {useEffect, useState} from 'react';
import FormWrapper from "../forms/FormWrapper";
import TextInput from "../forms/TextInput";
import emailLogo from "../../assets/svg/email.svg";
import useForm from "../../hooks/useForm";
import api from "../../services/api";
import Loading from "../Loading";

const Profile = () => {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    const validate = (values) => {
        const errors = {};
        // Ajoutez ici votre logique de validation
        return errors;
    };

    const initialValues = {email: '', username: ''};
    const {values, errors, handleChange, handleSubmit, setValues} = useForm(initialValues, validate);

    useEffect(() => {
        async function fetchUserData() {
            try {
                const response = await api.get('profile');
                const data = await response.json();
                setUser(data);
                setValues(data);
                setLoading(false);
            } catch (error) {
                console.log('errreur')
                // setLoading(false);
            }
            console.log(user)
        }

        fetchUserData();
    }, [setValues]);

    const onSubmit = async (formData) => {
        // setValues({email: 'erererw', username: 'dfsfdssfd
        // try {
        const res = api.patch(`users/${user.id}`, {json: {"email": formData.email,"username": formData.username}});

        console.log(res)
        // Logique pour soumettre le formulaire
        console.log(formData);
        // } catch (error) {
        //     console.error('Erreur lors de la soumission du formulaire:', error);
        // }
    };

    if (loading) return <Loading></Loading>;

    return (<div className="h-screen flex items-center justify-center">
            <div
                className="flex flex-col w-full md:w-1/2 xl:w-2/5 2xl:w-2/5 3xl:w-1/3 mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 max-w-3xl">
                <div className="flex flex-row gap-3 pb-4">
                    <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mon profil</h1>
                </div>

                <FormWrapper onSubmit={handleSubmit(onSubmit)}>
                    <TextInput
                        name="email"
                        label="Email"
                        value={values.email}
                        placeholder="satoshin@gmx.com"
                        onChange={handleChange}
                        error={errors.email}
                        logo={emailLogo}
                    />
                    <TextInput
                        name="username"
                        label="Username"
                        value={values.username}
                        placeholder="Nom d'utilisateur"
                        onChange={handleChange}
                        error={errors.username}
                        logo={emailLogo}
                    />
                    <button type="submit"
                            className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                        Mettre à jour
                    </button>
                </FormWrapper>
            </div>
        </div>);
};

export default Profile;