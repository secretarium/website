import { getStrapiMedia } from "@nx-secretarium-website/store/media";

const Image = ({image}) => {
    const imageUrl = getStrapiMedia(image);

    return (
      <img
        src={imageUrl}
        alt={image.alternativeText || image.name}
        height="300"
        width='100%'
      />
    );
}

export default Image;