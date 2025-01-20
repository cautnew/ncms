const appHeader = document.querySelector('body header');
const appFooter = document.querySelector('body footer');
const appMainContent = document.querySelector('main.app-main-content');

const resizeAppMainContent = function () {
  const windowHeight = window.innerHeight;
  const appMainContentTop = appMainContent.offsetTop;
  const appFooterHeight = appFooter.offsetHeight;

  const appMainContentHeight = windowHeight - appMainContentTop - appFooterHeight;
  appMainContent.style.minHeight = `${appMainContentHeight}px`;
}

window.addEventListener('resize', resizeAppMainContent);
window.addEventListener('load', resizeAppMainContent);
