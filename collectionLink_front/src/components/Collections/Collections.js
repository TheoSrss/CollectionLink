import React, {useEffect, useState} from 'react';
import api from "../../services/api";
import Loading from "../Loading";
import {useAuth} from "../../context/AuthContext";
import TextInput from "../forms/TextInput";
import MultipleSelectInput from "../forms/MultipleSelectInput";
import FormWrapper from "../forms/FormWrapper";
import Modal from "../Modal";
import useForm from "../../hooks/useForm";
import {formatDateTime} from "../../utils/date";
import Table from "../Table";
import Clipboard from "../Clipboard";
import Toggle from "../forms/Toggle";
import {KeySquare, LetterText, Type} from "lucide-react";

const Collections = () => {
    const [collections, setCollections] = useState(null);
    const [loading, setLoading] = useState(true);
    const {user} = useAuth();
    const [items, setItems] = useState(null);
    const [formOpened, setFormOpened] = useState(false);

    const defaultCollection = {
        name: '', description: '', collectable: [], changePassword: null, password: null
    }
    const [selectedItems, setSelectedItems] = useState([]);
    const [deleteModalOpen, setDeleteModalOpen] = useState(false);
    const [collectionToDelete, setCollectionToDelete] = useState(null);
    const [formError, setFormError] = useState(false);
    const [passwordExist, setPasswordExist] = useState(false);

    const {handleChange, handleSubmit, values, updateValues, handleApiErrors, errors} = useForm(defaultCollection);


    // Datatable
    const [sortColumn, setSortColumn] = useState(null);
    const [sortOrder, setSortOrder] = useState("asc");
    const [totalItems, setTotalItems] = useState(0);
    const [currentPage, setCurrentPage] = useState(1);

    const handleSort = (column) => {
        if (sortColumn === column) {
            setSortOrder(sortOrder === "asc" ? "desc" : "asc");
        } else {
            setSortColumn(column);
            setSortOrder("asc");
        }
    };
    const handleCurrentPage = (newPage) => {
        if (newPage >= 1 && newPage <=  Math.ceil(totalItems / 5)) {
            setCurrentPage(newPage);
        }
    };
    // Datatable

    useEffect(() => {
        fetchCollectionsData();
        if (user) {
            fetchItemsData();
        }
    }, [user,,currentPage,sortOrder]);


    const fetchCollectionsData = async () => {
        try {
            const queryParams = new URLSearchParams({
                user: user.id,
                page: currentPage,
                'order[name]': sortOrder
            }).toString();
            const response = await api.get(`collections?${queryParams}`);
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
        } finally {

        }
    };

    const handleOpenForm = async (collection = null) => {
        if (collections == null) {
            await fetchItemsData();
        }
        if (collection === null) {
            collection = defaultCollection;
        }
        handleApiErrors([]);
        setPasswordExist(collection.private);
        updateValues(collection);
        setSelectedItems(collection.collectable);
        setFormOpened(true);
        setFormError(false);
    };

    if (loading) {
        return <Loading/>;
    }

    // Modal logic
    const handleSelectionChange = (newSelectedItems) => {
        setSelectedItems(newSelectedItems);
    };
    const onSubmit = async (formData) => {
        try {
            let endpoint = formData['@id'] ?? 'collections';
            let apiMethod = formData['@id'] ? api.patch : api.post;
            let payload = {
                name: formData.name,
                description: formData.description,
                collectable: selectedItems.map((item) => item['@id']),
                changePassword: passwordExist && formData.deletePassword ? true : formData.changePassword,
                password: formData.password,
            };
            const res = await apiMethod(endpoint.replace('/api/', ''), {json: payload});
            if (res.ok) {
                setFormOpened(false);
                await fetchCollectionsData();
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
    };


    // Delete logic
    const handleDelete = async () => {
        try {
            const res = await api.delete(collectionToDelete.replace('/api/', ''));
            if (res.ok) {
                setDeleteModalOpen(false);
                await fetchCollectionsData();
            }
        } catch (error) {

        }
    };

    const handleOpenDelete = (collection) => {
        setCollectionToDelete(collection['@id']);
        setDeleteModalOpen(true);
    };

    // const navigate = useNavigate();

    return (<div className="h-screen flex items-center justify-center">
        {formOpened && (<Modal setModalOpen={setFormOpened}>
            <FormWrapper onSubmit={handleSubmit(onSubmit)} error={formError}>
                <TextInput
                    name="name"
                    label="Titre"
                    value={values.name}
                    placeholder="Collection de vinyle"
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
                <MultipleSelectInput
                    label="Choisissez vos items"
                    name="items"
                    options={items}
                    itemTitle="name"
                    selectedValues={selectedItems}
                    onChange={handleSelectionChange}
                    error={errors.collectable}
                />
                {passwordExist && (
                    <p className="mb-6">Cette collection est actuellement en privée. Un mot de passe est requis pour la
                        consultation.</p>)}
                {passwordExist && !values.changePassword && (<Toggle
                    name="deletePassword"
                    label='Supprimer le mot de passe (va rendre la collection public)'
                    value={values.deletePassword}
                    onChange={handleChange}
                    error={errors.deletePassword}
                />)}
                <Toggle
                    name="changePassword"
                    label={passwordExist ? 'Nouveau mot de passe' : 'Ajouter un mot de passe'}
                    value={values.changePassword}
                    onChange={handleChange}
                    error={errors.changePassword}
                />
                {values.changePassword && (<TextInput
                    name="password"
                    label="Mot de passe"
                    value={values.password}
                    onChange={handleChange}
                    logo={<KeySquare/>}
                    error={errors.password}
                />)}
                <button type="submit"
                        className="w-full text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-6">
                    {values['@id'] ? 'Mettre à jour' : 'Créer'}
                </button>
            </FormWrapper>
        </Modal>)}
        {deleteModalOpen && (<Modal setModalOpen={setDeleteModalOpen}>
            <div className="p-6">
                <h2 className="text-lg font-semibold mb-4">Confirmer la suppression</h2>
                <p className="mb-6">Êtes-vous sûr de vouloir supprimer cette collection ?</p>
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
                    Nouvelle collection
                </button>
            </div>
            <div className="flex flex-row gap-3 pb-4">
                <h1 className="text-3xl font-bold text-[#4B5563] text-[#4B5563] my-auto">Mes collections</h1>
            </div>
            <Table
                   columns={[{key: "name", label: "Titre", sortable: true}, {
                       key: "createdAt", label: "Date de création", sortable: true
                   }, {key: "numberItems", label: "Nombre d'items"}, {
                       key: "actions", label: "Actions",
                   },]}
                   handleSort={handleSort}
                   sortColumn={sortColumn}
                   sortOrder={sortOrder}
                   totalItem={totalItems}
                   currentPage={currentPage}
                   handleCurrentPage={handleCurrentPage}
            >
                {collections.map(collection => (<tr key={collection['@id']}>
                    <td>{collection.name}</td>
                    <td>{formatDateTime(collection.createdAt)}</td>
                    <td>{collection.collectable.length}</td>
                    <td>
                        <div className="flex justify-center space-x-4">
                            <Clipboard url={`${window.location.origin}/${collection.slug}`} label="Copier le lien" />
                            <button
                                onClick={() => handleOpenForm(collection)}
                                className="text-[#FFFFFF] bg-[#4F46E5] focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-2">
                                Modifier
                            </button>
                            <button
                                onClick={() => handleOpenDelete(collection)}
                                className="text-[#FFFFFF] bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center my-2 "
                            >
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>))}
            </Table>
        </div>
    </div>);
};

export default Collections;