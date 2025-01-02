import React, {useEffect, useState} from 'react';
import api from "../../services/api";
import Loading from "../Loading";
import {useAuth} from "../../context/AuthContext";
import TextInput from "../forms/TextInput";
import MultipleSelectInput from "../forms/MultipleSelectInput";
import descriptionLogo from "../../assets/svg/description.svg";
import titleLogo from "../../assets/svg/title.svg";
import FormWrapper from "../forms/FormWrapper";
import Modal from "../Modal";
import useForm from "../../hooks/useForm";

const Collections = () => {
    const [collections, setCollections] = useState(null);
    const [loading, setLoading] = useState(true);
    const {user} = useAuth();
    const [items, setItems] = useState(null);
    const [formOpened, setFormOpened] = useState(false);
    const [collectionEdited, setCollectionEdited] = useState({
        name: '', description: '', collectable: []
    });
    const [selectedItems, setSelectedItems] = useState([]);

    // Modal logic
    const validate = (values) => {
        const errors = {};
        return errors;
    };
    const {handleChange, handleSubmit, values, updateValues} = useForm(collectionEdited, validate);
    // Modal logic

    useEffect(() => {
        if (user) {
            fetchCollectionsData();
            fetchItemsData();
        }
    }, [user,]);

    const fetchCollectionsData = async () => {
        try {
            const response = await api.get(`collections?user=${user.id}`);
            const data = await response.json();
            setCollections(data["member"]);
            setLoading(false);
        } catch (error) {
            setLoading(false);
        }
    }

    const fetchItemsData = async () => {
        try {
            const response = await api.get(`collectables?creator=${user.id}`);
            const data = await response.json();
            setItems(data["member"]);
        } catch (error) {
            console.error('Error:', error);
        } finally {

        }
    };

    const handleOpenForm = async (collection = null) => {
        if (collections == null) {
            await fetchItemsData();
        }
        if (collection === null) {
            collection = {
                name: '', description: '', collectable: []
            }
        }
        updateValues(collection);
        setSelectedItems(collection.collectable);
        setFormOpened(true);
    };

    if (loading) {
        return <Loading/>;
    }

    // Modal logic
    const handleSelectionChange = (newSelectedItems) => {
        setSelectedItems(newSelectedItems);
    };
    const onSubmit = async (formData) => {
        // try {
        let endpoint = formData['@id'] ?? 'collections';
        let apiMethod = formData['@id'] ? api.patch : api.post;
        console.log(formData,endpoint);
        let payload = {
            name: formData.name, description: formData.description, collectable: selectedItems.map((item) => item['@id']),
        };
        const res = await apiMethod(endpoint.replace('/api/',''), { json: payload });
        if (res.ok) {
            setFormOpened(false);
            await fetchCollectionsData();
        }
        // } catch (error) {
        // }
    };

    return (<div className="h-screen flex items-center justify-center">
        {formOpened ? <Modal setModalOpen={setFormOpened}>
            <FormWrapper onSubmit={handleSubmit(onSubmit)}>
                <TextInput
                    name="name"
                    label="Titre"
                    value={values.name}
                    placeholder="Collection de vinyle"
                    onChange={handleChange}
                    logo={titleLogo}
                />
                <TextInput
                    name="description"
                    label="Description"
                    value={values.description}
                    onChange={handleChange}
                    logo={descriptionLogo}
                />
                <MultipleSelectInput
                    label="Choisissez vos items"
                    name="items"
                    options={items}
                    itemTitle="name"
                    selectedValues={selectedItems}
                    onChange={handleSelectionChange}
                />
                <button type="submit"
                        className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    {values['@id'] ? 'Mettre à jour' : 'Créer'}
                </button>
            </FormWrapper>
        </Modal> : null}
        <div
            className="flex flex-col w-full mx-auto p-8 md:p-10 2xl:p-12 3xl:p-14 bg-[#ffffff] rounded-2xl shadow-xl dark:bg-gray-800 ">
            <div>
                <button
                    onClick={() => handleOpenForm()}
                    className="float-right text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    Nouvelle collection
                </button>
            </div>
            <div className="flex flex-row gap-3 pb-4">
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mes collections</h1>
            </div>


            <table className="table-auto">
                <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de creation</th>
                    <th>Nombre d'items</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                {collections.map(collection => (<tr key={collection['@id']}>
                    <td>{collection.name}</td>
                    <td>DATE</td>
                    <td>{collection.collectable.length}</td>
                    <td className='text-center'>
                        <button
                            onClick={() => handleOpenForm(collection)}
                            className="text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                            Modifier
                        </button>
                    </td>
                </tr>))}
                </tbody>
            </table>
        </div>
    </div>);
};

export default Collections;