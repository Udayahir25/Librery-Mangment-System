import Link from "next/link";

export default function HomePage() {
	return (
		<main className="min-h-screen flex items-center justify-center p-6">
			<div className="max-w-xl w-full space-y-6 text-center">
				<h1 className="text-3xl font-bold">Employee Management System</h1>
				<p className="text-muted-foreground">
					Sign in to access your dashboard.
				</p>
				<div className="flex items-center justify-center gap-4">
					<Link className="underline" href="/login">
						Login
					</Link>
					<Link className="underline" href="/employee">
						Employee
					</Link>
					<Link className="underline" href="/admin">
						Admin
					</Link>
				</div>
			</div>
		</main>
	);
}