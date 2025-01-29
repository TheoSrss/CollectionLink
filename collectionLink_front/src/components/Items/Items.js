import React, {useEffect, useState} from 'react';
import api from "../../services/api";
import Loading from "../Loading";
import {useAuth} from "../../context/AuthContext";
import {formatDateTime} from "../../utils/date";
import Table from "../Table";
import Modal from "../Modal";
import useForm from "../../hooks/useForm";
import FormWrapper from "../forms/FormWrapper";
import TextInput from "../forms/TextInput";
import Toggle from "../forms/Toggle";
import {LetterText, Type} from "lucide-react";

const Items = () => {
    const [items, setItems] = useState(null);
    const [loading, setLoading] = useState(true);
    const {user} = useAuth();
    const [itemToDelete, setItemToDelete] = useState(null);
    const [deleteModalOpen, setDeleteModalOpen] = useState(false);
    const [itemEdited, setItemEdited] = useState({
        name: '', description: '', collectable: []
    });
    const [formOpened, setFormOpened] = useState(false);
    const [formError, setFormError] = useState(false);

    const {handleChange, handleSubmit, values, setValues, handleApiErrors, errors} = useForm(itemEdited);

    useEffect(() => {
        fetchItemsData();
    }, [user]);

    const fetchItemsData = async () => {
        try {
            const response = await api.get(`collectables?creator=${user.id}`);
            const data = await response.json();
            setItems(data["member"]);
        } catch (error) {
            setLoading(true);
        } finally {
            setLoading(false);
        }
    }

    const truncateDescription = (description) => {
        if (!description) return '...';
        return description.length > 50 ? `${description.slice(0, 50)}...` : description;
    };


    // Delete logic
    const handleDelete = async () => {
        try {
            const res = await api.delete(itemToDelete.replace('/api/', ''));
            if (res.ok) {
                setDeleteModalOpen(false);
                await fetchItemsData();
            }
        } catch (error) {

        }
    };

    const handleOpenDelete = (item) => {
        setItemToDelete(item['@id']);
        setDeleteModalOpen(true);
    };


    const handleOpenForm = async (item = null) => {
        if (item === null) {
            item = {
                name: '', description: '', public: []
            }
        }
        handleApiErrors([]);
        setValues(item);
        setFormOpened(true);
        setFormError(false);
    };

    const onSubmit = async (formData) => {
        try {
            let endpoint = formData['@id'] ?? 'collectables';
            let apiMethod = formData['@id'] ? api.patch : api.post;
            let payload = {
                name: formData.name, description: formData.description, public: !!formData.public,
            };
            const res = await apiMethod(endpoint.replace('/api/', ''), {json: payload});
            if (res.ok) {
                setFormOpened(false);
                await fetchItemsData();
            }
        } catch (error) {
            if (error.response && error.response.status === 422) {
                const data = await error.response.json();
                if (data.violations) {
                    handleApiErrors(data.violations);
                }
            } else {
                setFormError(true);
            }
        }
    }

    if (loading) {
        return <Loading></Loading>;
    }
    return (<div className="h-screen flex items-center justify-center">
        {formOpened && (<Modal setModalOpen={setFormOpened}>
            <FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
                <TextInput
                    name="name"
                    label="Item"
                    value={values.name}
                    placeholder="Mon item"
                    onChange={handleChange}
                    logo={<Type/>}
                    error={errors.name}
                />
                <TextInput
                    name="description"
                    label="Description"
                    value={values.description}
                    onChange={handleChange}
                    logo={<LetterText/>}
                    error={errors.description}
                    textarea={true}
                />
                <Toggle
                    name="public"
                    label="Public"
                    value={values.public ?? false}
                    onChange={handleChange}
                    error={errors.public}
                />
                <button type="submit"
                        className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    {values['@id'] ? 'Mettre à jour' : 'Créer'}
                </button>
            </FormWrapper>
        </Modal>)}
        {deleteModalOpen && (<Modal setModalOpen={setDeleteModalOpen}>
            <div className="p-6">
                <h2 className="text-lg font-semibold mb-4">Confirmer la suppression</h2>
                <p className="mb-6">Êtes-vous sûr de vouloir supprimer cet item ?</p>
                <div className="flex justify-end gap-4">
                    <button
                        onClick={() => setDeleteModalOpen(false)}
                        className="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100"
                    >
                        Annuler
                    </button>
                    <button
                        onClick={handleDelete}
                        className="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </Modal>)}
        <div
            className="flex flex-col w-full mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 ">
            <div>
                <button
                    onClick={() => handleOpenForm()}
                    className="float-right text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    Nouvel item
                </button>
            </div>
            <div className="flex flex-row gap-3 pb-4">
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mes items</h1>
            </div>
            <Table columns={['Item', 'Date de création', 'Description', 'Actions']}>
                {items.map(item => (<tr key={item['@id']}>
                    <td>{item.name}</td>
                    <td>{formatDateTime(item.createdAt)}</td>
                    <td>
                        {truncateDescription(item?.description)}
                    </td>
                    <td>
                        <div className="flex justify-center space-x-4">
                            {!item.public && (<div className="flex justify-center space-x-4">
                                <button
                                    onClick={() => handleOpenForm(item)}
                                    className="text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-2">
                                    Modifier
                                </button>
                                <button
                                    onClick={() => handleOpenDelete(item)}
                                    className="text-[#FFFFFF] bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-2 "
                                >
                                    Supprimer
                                </button>
                            </div>)}

                        </div>
                    </td>
                </tr>))}
            </Table>
        </div>
    </div>);
};

export default Items;