import {Card, Carousel} from "flowbite-react";
import {truncateText} from "../../utils/string";
import {BACKEND_URL} from '../../constants';

const PublicCard = ({collectable}) => {
    return (

        <div className="w-72 h-[500px] flex flex-col">
            <Card className="flex flex-col h-full">
                <div className="h-96 w-full flex justify-center items-center ">
                    <Carousel slide={false} className="h-full">
                        {collectable.pictures.map((img, index) => (<img
                            src={`${BACKEND_URL}${img.url}`}
                            alt={img.name}
                            key={index}
                            className="h-full w-full object-contain"
                        />))}
                    </Carousel>
                </div>
                <div className="p-4 flex flex-col justify-between flex-grow">
                    <h5 className="text-lg font-bold text-gray-900 dark:text-white">
                        {collectable.name}
                    </h5>
                    <p className="text-gray-700 dark:text-gray-400 text-sm">
                        {truncateText(collectable.description, 100)}
                    </p>
                </div>
            </Card>
        </div>

    );
};

export default PublicCard;