export const API_BASE_URL = process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000/api';
export const TOKEN_KEY = 'auth_token';

const ERROR_FORM_MESSAGES = {
    DEFAULT: "Une erreur est survenue. Champ incorrecte.",
    "bd79c0ab-ddba-46cc-a703-a7a4b08de310": "L'email n'est pas valide.",
    "c1051bb4-d103-4f74-8988-acbcafc7fdc3": "Ce champ est obligatoire. Il ne peut pas être vide.",
    "23bd9dbf-6b9b-41cd-a99e-4844bcf3077f": "Cette valeur est déjà utilisée.",
    "d94b19cc-114f-4f44-9cc4-4138e80a87b9": "Cette valeur est trop longue.",
    "9ff3fdc4-b214-49db-8718-39c315e33d45": "Cette valeur est trop courte.",
    "bef8e338-6ae5-4caf-b8e2-50e7b0579e69": "Il faut sélectionner au moins un élément.",
    "1a2b3c4d-5e6f-7g8h-9i10-j11k12l13m14": "Cet utilisateur n'existe pas.",
    "3c4d5e6f-7g8h-9i10-j11k-12l13m14n162": "Ce code n'est pas correct.",
};
export const getErrorMessage = (errorCode) => {
    return ERROR_FORM_MESSAGES[errorCode] || "Une erreur est survenue. Champ incorrecte.";
};
