const LogoImgPath = (id: number) => {
  const minNumber = 1;
  const maxNumber = 7;
  
  if (id) {
    if (id < minNumber) {
      id = minNumber;
    }

    if (id > maxNumber) {
      id = maxNumber;
    }

    return new URL(`../../img/logo/LOGO_${id}.png`, import.meta.url).href;
  }

  const randomNumber = () => Math.floor(Math.random() * (maxNumber - minNumber + 1)) + minNumber;

  return new URL(`../../img/logo/LOGO_${randomNumber()}.png`, import.meta.url).href;
};

export default LogoImgPath;