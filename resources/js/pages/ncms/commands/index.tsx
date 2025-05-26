import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import CommandLayout from '@/layouts/command/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Commands',
        href: '/ncms/commands',
    },
];

interface CommandsList {
  name: string;
  description: string;
  status: string;
}

interface PageParams {
  commands_list: CommandsList[];
}

export default function Index({ commands_list }: PageParams) {
  const ElCommandsList = () => {
    const elements = [];
    commands_list.forEach((command, index) => {
      elements.push(<div key={index} className="flex justify-between items-center">
        <p className="text-sm text-neutral-600">{command.name}</p>
        <p className="text-sm text-neutral-600">{command.status}</p>
        <p className="text-sm text-neutral-600">{command.description}</p>
      </div>);
    });
    return <div>{elements}</div>;
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Commands" />

      <CommandLayout>
        <HeadingSmall title="Commands" description="Follow the commands available and the status." />
        <ElCommandsList />
      </CommandLayout>
    </AppLayout>
  );
}
