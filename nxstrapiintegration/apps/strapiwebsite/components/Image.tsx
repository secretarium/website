import { getStrapiMedia } from "../lib/media";

const Image = ({image}) => {
    const imageUrl = getStrapiMedia(image);

    return (
      <img
        src={imageUrl}
        alt={image.alternativeText || image.name}
      />
    );
}

export default Image;