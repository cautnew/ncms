import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, useForm, Link } from '@inertiajs/react';
import {
    ColumnDef,
    flexRender,
    getCoreRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    SortingState,
    useReactTable,
    VisibilityState,
} from '@tanstack/react-table';
import { ArrowDown, ArrowUp, ArrowUpDown, ChevronDown, Eye, EyeOff, X } from 'lucide-react';
import * as React from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Routes',
        href: '/routes',
    },
];

export type Payment = {
    id: string;
    amount: number;
    status: 'pending' | 'processing' | 'success' | 'failed';
    email: string;
};

// Generate more data for pagination demo
const generateData = (count: number): Payment[] => {
    const statuses = ['pending', 'processing', 'success', 'failed'] as const;
    const domains = ['example.com', 'gmail.com', 'yahoo.com', 'hotmail.com'];
    const authors = ['Felipe', 'Fulano', 'Sicrano'];

    return Array.from({ length: count }, (_, i) => ({
        id: `payment-${i + 1}`,
        amount: Math.floor(Math.random() * 1000) + 50,
        status: statuses[Math.floor(Math.random() * statuses.length)],
        author: authors[Math.floor(Math.random() * authors.length)],
        email: `user${i + 1}@${domains[Math.floor(Math.random() * domains.length)]}`,
    }));
};
const data: Payment[] = generateData(50);

export const columns: ColumnDef<Payment>[] = [
    {
        accessorKey: 'status',
        header: ({ column }) => {
            console.log(column);
            const isSortedAsc = column.getIsSorted() === 'asc';
            const isSortedDesc = column.getIsSorted() === 'desc';
            let btnSorting = null;
            if (isSortedAsc) {
                btnSorting = <ArrowUp className="ml-2 h-4 w-4" />;
            } else if (isSortedDesc) {
                btnSorting = <ArrowDown className="ml-2 h-4 w-4" />;
            } else {
                btnSorting = <ArrowUpDown className="ml-2 h-4 w-4" />;
            }

            return (
                <Button variant="ghost" onClick={() => column.toggleSorting(isSortedAsc)} onDoubleClick={() => column.clearSorting()}>
                    Status {btnSorting}
                </Button>
            );
        },
        enableSorting: true,
        enableHiding: true,
        cell: ({ row }) => <div className="capitalize">{row.getValue('status')}</div>,
    },
    {
        accessorKey: 'email',
        header: ({ column }) => {
            return 'E-mail';
        },
        enableSorting: true,
        enableHiding: true,
        cell: ({ row }) => <div className="lowercase">{row.getValue('email')}</div>,
    },
    {
        accessorKey: 'author',
        header: 'Author',
        cell: ({ row }) => <div>{row.getValue('author')}</div>,
    },
    {
        accessorKey: 'amount',
        header: () => <div className="text-right">Amount</div>,
        cell: ({ row }) => {
            const amount = parseFloat(row.getValue('amount'));
            const formatted = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }).format(amount);
            return <div className="text-right font-medium">{formatted}</div>;
        },
    },
];

export default function DataTablePagination() {
    const { lastCachedAt } = usePage().props as any;
    const { post, processing } = useForm();
    const [showAlert, setShowAlert] = React.useState(true);
    
    const [columnVisibility, setColumnVisibility] = React.useState<VisibilityState>({
        id: false, // Hide ID column by default
    });
    const [sorting, setSorting] = React.useState<SortingState>([]);

    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        onSortingChange: setSorting,
        onColumnVisibilityChange: setColumnVisibility,
        initialState: {
            pagination: {
                pageSize: 5,
            },
        },
        state: {
            columnVisibility,
            sorting,
        },
    });

    const visibleColumns = table.getAllColumns().filter((column) => column.getIsVisible());
    const hiddenColumns = table.getAllColumns().filter((column) => !column.getIsVisible());

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pages" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                {showAlert && (
                    <div className="relative rounded-lg border bg-card text-card-foreground shadow-sm p-6 mb-4">
                        <button 
                            onClick={() => setShowAlert(false)} 
                            className="absolute top-4 right-4 text-muted-foreground hover:text-foreground"
                            aria-label="Dismiss notice"
                        >
                            <X className="h-5 w-5" />
                        </button>
                        <h3 className="text-lg font-semibold mb-2">Route cache</h3>
                        <p className="text-sm text-muted-foreground mb-4 pr-6">
                            Route caching improves Laravel performance. Whenever you add or change routes, rebuild this cache so
                            updates take effect.
                        </p>
                        
                        <div className="flex items-center md:justify-between flex-col md:flex-row mt-4">
                            <div className="text-sm border-l-4 border-indigo-500 pl-3 py-3 md:py-0">
                                <span className="font-medium text-gray-500 block">Last cache update:</span>
                                <span className="text-gray-900 dark:text-gray-100 font-semibold">
                                    {lastCachedAt ? lastCachedAt : 'No cache (pure runtime)'}
                                </span>
                            </div>
                            
                            <div className="flex gap-2 mt-4 md:mt-0">
                                <Button variant="outline" asChild>
                                    <Link href={route('routes.cache.details')}>More details</Link>
                                </Button>
                                <Button 
                                    onClick={() => post(route('routes.cache'), { preserveScroll: true })} 
                                    disabled={processing}
                                    variant="default"
                                >
                                    {processing ? 'Building…' : 'Build route cache'}
                                </Button>
                            </div>
                        </div>
                    </div>
                )}

                <div className="flex w-full flex-col gap-y-2 py-2 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center justify-between space-x-2">
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline" className="ml-auto">
                                    <Eye className="mr-2 h-4 w-4" />
                                    View <ChevronDown className="ml-2 h-4 w-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" className="w-[200px]">
                                <DropdownMenuLabel>Toggle columns</DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                {table
                                    .getAllColumns()
                                    .filter((column) => column.getCanHide())
                                    .map((column) => {
                                        return (
                                            <DropdownMenuCheckboxItem
                                                key={column.id}
                                                className="capitalize"
                                                checked={column.getIsVisible()}
                                                onCheckedChange={(value) => column.toggleVisibility(!!value)}
                                            >
                                                <div className="flex items-center space-x-2">
                                                    {column.getIsVisible() ? <Eye className="h-4 w-4" /> : <EyeOff className="h-4 w-4" />}
                                                    <span>{column.id}</span>
                                                </div>
                                            </DropdownMenuCheckboxItem>
                                        );
                                    })}
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <div className="textext-center flex items-center space-x-4 text-sm text-muted-foreground">
                            <span>{visibleColumns.length} visible</span>
                            <span>{hiddenColumns.length} hidden</span>
                        </div>
                    </div>
                    <div className="flex items-center justify-end space-x-2">
                        <p className="text-sm font-medium">Rows per page</p>
                        <select
                            value={table.getState().pagination.pageSize}
                            onChange={(e) => {
                                table.setPageSize(Number(e.target.value));
                            }}
                            className="h-8 w-[70px] rounded border border-input bg-background px-3 py-1 text-sm"
                        >
                            {[5, 10, 20, 30, 40].map((pageSize) => (
                                <option key={pageSize} value={pageSize}>
                                    {pageSize}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div className="flex items-center justify-end space-x-4 lg:space-x-6">
                        <div className="flex w-[100px] items-center justify-center text-sm font-medium">
                            Page {table.getState().pagination.pageIndex + 1} of {table.getPageCount()}
                        </div>
                        <div className="flex items-center space-x-2">
                            <Button variant="outline" size="sm" onClick={() => table.setPageIndex(0)} disabled={!table.getCanPreviousPage()}>
                                First
                            </Button>
                            <Button variant="outline" size="sm" onClick={() => table.previousPage()} disabled={!table.getCanPreviousPage()}>
                                Previous
                            </Button>
                            <Button variant="outline" size="sm" onClick={() => table.nextPage()} disabled={!table.getCanNextPage()}>
                                Next
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                onClick={() => table.setPageIndex(table.getPageCount() - 1)}
                                disabled={!table.getCanNextPage()}
                            >
                                Last
                            </Button>
                        </div>
                    </div>
                </div>
                <div className="overflow-hidden rounded-md border">
                    <Table>
                        <TableHeader>
                            {table.getHeaderGroups().map((headerGroup) => (
                                <TableRow key={headerGroup.id}>
                                    {headerGroup.headers.map((header) => {
                                        return (
                                            <TableHead key={header.id}>
                                                {header.isPlaceholder ? null : flexRender(header.column.columnDef.header, header.getContext())}
                                            </TableHead>
                                        );
                                    })}
                                </TableRow>
                            ))}
                        </TableHeader>
                        <TableBody>
                            {table.getRowModel().rows?.length ? (
                                table.getRowModel().rows.map((row) => (
                                    <TableRow key={row.id} data-state={row.getIsSelected() && 'selected'}>
                                        {row.getVisibleCells().map((cell) => (
                                            <TableCell key={cell.id}>{flexRender(cell.column.columnDef.cell, cell.getContext())}</TableCell>
                                        ))}
                                    </TableRow>
                                ))
                            ) : (
                                <TableRow>
                                    <TableCell colSpan={columns.length} className="h-24 text-center">
                                        No pages found.
                                    </TableCell>
                                </TableRow>
                            )}
                        </TableBody>
                    </Table>
                </div>
            </div>
        </AppLayout>
    );
}
