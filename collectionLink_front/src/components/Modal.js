import React, {useEffect} from 'react';

const Modal = ({setModalOpen, children}) => {
    const closeModal = (collection = null) => {
        setModalOpen(false)
    };
    const handleBackgroundClick = (e) => {
        if (e.target === e.currentTarget) {
            setModalOpen(false);
        }
    };
    useEffect(() => {
        const close = (e) => {
            if(e.keyCode === 27){
                setModalOpen(false);
            }
        }
        window.addEventListener('keydown', close)
        return () => window.removeEventListener('keydown', close)
    },[])
    return (<div className="fixed inset-0 bg-opacity-50 flex items-center justify-center z-50"
                 onClick={handleBackgroundClick}>
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 p-6 relative">
            <button
                onClick={() => closeModal()}
                className="absolute top-4 right-4 text-gray-600 hover:text-gray-800 focus:outline-none"
                aria-label="Fermer la modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div>{children}</div>
        </div>
    </div>);
};

export default Modal;