import Link from "next/link";

export default async function AdminDashboardPage() {
	return (
		<main className="p-6 space-y-4">
			<h1 className="text-2xl font-semibold">Admin Dashboard</h1>
			<div className="grid gap-4 grid-cols-1 md:grid-cols-2">
				<Link className="underline" href="/admin/attendance">Attendance</Link>
				<Link className="underline" href="/admin/salary">Salary</Link>
				<Link className="underline" href="/admin/projects">Projects</Link>
				<Link className="underline" href="/admin/leaves">Leaves</Link>
			</div>
		</main>
	);
}