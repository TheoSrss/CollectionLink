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
import File from "../forms/File";
import {LetterText, Type} from "lucide-react";
import {truncateText} from "../../utils/string";

const Items = () => {
    const [items, setItems] = useState(null);
    const [loading, setLoading] = useState(true);
    const {user} = useAuth();
    const [itemToDelete, setItemToDelete] = useState(null);
    const [deleteModalOpen, setDeleteModalOpen] = useState(false);
    const [formOpened, setFormOpened] = useState(false);
    const [formError, setFormError] = useState(false);
    const defaultItem = {
        name: "", description: "", pictures: []
    };

    const {
        handleChange, handleSubmit, values, updateValues, handleApiErrors, errors, defaultValues
    } = useForm(defaultItem);

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
        fetchItemsData();
    }, [user,currentPage,sortOrder]);

    const fetchItemsData = async () => {
        try {
            const queryParams = new URLSearchParams({
                creator: user.id,
                page: currentPage,
                'order[name]': sortOrder
            }).toString();
            const response = await api.get(`collectables?${queryParams}`);
            const data = await response.json();
            setItems(data["member"]);
            setTotalItems(data.totalItems);
        } catch (error) {
            setLoading(true);
        } finally {
            setLoading(false);
        }
    }

    // Delete logic
    const handleDelete = async () => {
        if (!itemToDelete) return;
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

    const handleOpenForm = (item = null) => {
        handleApiErrors([]);
        updateValues(item ?? defaultItem);
        setFormOpened(true);
        setFormError(false);
    };

    const onSubmit = async (formData) => {

        try {
            let endpoint = formData['@id'] ?? 'collectables';
            let apiMethod = formData['@id'] ? api.patch : api.post;
            let payload = {
                name: formData.name, description: formData.description,
            };

            const res = await apiMethod(endpoint.replace('/api/', ''), {json: payload});
            let filesToAdd = [];
            let filesToDelete = [];
            let resPicturesDelete = null;
            let resPicturesAdd = null;

            if (res.ok) {
                let data = await res.json();
                const idPictures = data['@id'].replace('/api/', '');
                const filesList = formData.pictures;
                filesToDelete = defaultValues.pictures.filter(file => file['@id'] && !filesList.some(f => f['@id'] === file['@id']));
                filesToAdd = filesList.filter((file) => !file['@id']);

                if (filesToDelete.length > 0) {
                    resPicturesDelete = await api.post(`${idPictures}/pictures/delete`, {
                        json: {
                            'picturesIdsToDelete': filesToDelete.map(file => Number(file['@id'].split('/').pop())),
                        }
                    });
                }

                if (filesToAdd.length > 0) {
                    const picturesFormData = new FormData();
                    filesToAdd.forEach((file) => {
                        picturesFormData.append('pictures[]', file.file);
                    });
                    resPicturesAdd = await api.post(`${idPictures}/pictures`, {
                        body: picturesFormData,
                    });
                }
            }

            if (res.ok && (filesToDelete.length === 0 || resPicturesDelete?.ok) && (filesToAdd.length === 0 || resPicturesAdd?.ok)) {
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
                <File
                    name="pictures"
                    label="Photos (5 maximum)"
                    value={values.pictures}
                    onChange={handleChange}
                    error={errors.pictures}
                    max={5}
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
            <Table
                columns={[{key: "name", label: "Item", sortable: true}, {
                    key: "createdAt", label: "Date de création", sortable: true
                }, {key: "description", label: "Description"}, {
                    key: "actions", label: "Actions",
                },]}
                handleSort={handleSort}
                sortColumn={sortColumn}
                sortOrder={sortOrder}
                totalItem={totalItems}
                currentPage={currentPage}
                handleCurrentPage={handleCurrentPage}
            >
                {items.map(item => (<tr key={item['@id']}>
                    <td>{item.name}</td>
                    <td>{formatDateTime(item.createdAt)}</td>
                    <td>
                        {truncateText(item?.description, 100)}
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