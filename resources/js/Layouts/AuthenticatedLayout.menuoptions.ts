import { faScrewdriverWrench, faBuilding } from "@fortawesome/free-solid-svg-icons";

const sections = [
  {
    region: "main",
    name: "Dashboard",
    route: "ncms.dashboard"
  },
  {
    region: "main",
    name: "Pages",
    route: "ncms.pages.contents",
    submenu: [
      {
        name: "Page contents",
        route: "ncms.pages.contents"
      },
      {
        name: "Page models",
        route: "ncms.pages.models"
      }
    ]
  },
  {
    region: "main",
    name: "Financeiro",
    route: "ncms.financeiro",
    submenu: [
      {
        name: "Add new expenses",
        route: "ncms.financeiro.addexpenses"
      }
    ]
  },
  {
    region: "manager",
    icon: faScrewdriverWrench,
    name: "Tools",
    route: "ncms.manager.tools",
    submenu: [
      {
        name: "Ponto",
        route: "ncms.ponto",
        submenu: []
      }
    ]
  },
  {
    region: "manager",
    icon: faBuilding,
    name: "Enterprises",
    route: "ncms.manager.enterprises",
    submenu: []
  },
  {
    region: "account",
    name: "Profile",
    route: "ncms.profile.edit",
    submenu: []
  },
  {
    region: "account",
    name: "Log Out",
    route: "ncms.logout",
    submenu: []
  },
];

const getCurrentSection = (route: string, region: string) => {
  if (region) {
    const section = getSectionList(region);

    return section.find((section) => section.route === route) || {
      region: '',
      name: '',
      route: ''
    };
  }

  return sections.filter((section) => section.route === route) || {
    region: '',
    name: '',
    route: ''
  };
};

const getSectionList = (region: string) => {
  if (region && (region === '*' || region === 'all')) {
    return sections;
  }

  return sections.filter((section) => section.region === region) || {
    region: '',
    name: '',
    route: ''
  };
};

export default {
  sections,
  getCurrentSection,
  getSectionList
};
