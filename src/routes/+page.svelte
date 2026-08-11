<script lang="ts">
import type { SubmitFunction } from '@sveltejs/kit';
import { enhance } from '$app/forms';
import { page } from '$app/state';
import type { ActionData } from './$types';

let { form }: { form: ActionData } = $props();
let loading = $state(false);
let qrDataUrl = $state('');

// 発行済みシークレットの QR(/status?secret=... への URL)を描画する
$effect(() => {
	const secret = form?.secret;
	if (!secret) return;
	(async () => {
		const QRCode = (await import('qrcode')).default;
		const url = `${page.url.origin}/status?secret=${secret}`;
		qrDataUrl = await QRCode.toDataURL(url, { width: 280, margin: 2 });
	})();
});

const submitHandler: SubmitFunction = () => {
	loading = true;
	return async ({ update }) => {
		await update({ reset: false });
		loading = false;
	};
};
</script>

<svelte:head>
	<title>QR Entry System</title>
</svelte:head>

{#if form?.secret}
	<div class="flex flex-col items-center gap-6">
		<div class="card bg-base-100 w-full max-w-md shadow-lg">
			<div class="card-body items-center gap-4 text-center">
				<h1 class="card-title">あなたのシークレット</h1>
				<p class="font-mono text-3xl font-bold tracking-wider">{form.secret}</p>
				{#if qrDataUrl}
					<img src={qrDataUrl} alt="入場用QRコード" width="280" height="280">
				{:else}
					<div class="skeleton h-70 w-70"></div>
				{/if}
				<p class="text-base-content/70 text-sm">
					このQRコードを受付でご提示ください。シークレットは再発行に必要なので控えてください。
				</p>
			</div>
		</div>
	</div>
{:else}
	<div class="flex flex-col items-center gap-8">
		<section class="w-full max-w-md">
			<h1 class="mb-4 text-2xl font-bold">入場登録</h1>
			<div class="card bg-base-100 shadow-md">
				<div class="card-body gap-4">
					{#if form?.error}
						<div class="alert alert-error text-sm">{form.error}</div>
					{/if}
					<form
						method="POST"
						action="?/register"
						use:enhance={submitHandler}
						class="flex flex-col gap-4"
					>
						<label class="form-control w-full">
							<span class="label-text mb-1">名前</span>
							<input
								name="name"
								type="text"
								required
								maxlength="255"
								class="input input-bordered w-full"
							>
						</label>
						<label class="form-control w-full">
							<span class="label-text mb-1">連絡先</span>
							<input
								name="contact"
								type="text"
								required
								maxlength="255"
								class="input input-bordered w-full"
							>
						</label>
						<label class="form-control w-full">
							<span class="label-text mb-1">住所</span>
							<input
								name="address"
								type="text"
								required
								maxlength="255"
								class="input input-bordered w-full"
							>
						</label>
						<button type="submit" class="btn btn-primary" disabled={loading}>
							{#if loading}
								<span class="loading loading-spinner"></span>
							{/if}
							登録
						</button>
					</form>
				</div>
			</div>
		</section>

		<section class="w-full max-w-md">
			<h2 class="mb-4 text-xl font-semibold">QRチケットの再表示</h2>
			<div class="card bg-base-100 shadow-md">
				<div class="card-body gap-4">
					{#if form?.reissueError}
						<div class="alert alert-warning text-sm">{form.reissueError}</div>
					{/if}
					<form
						method="POST"
						action="?/reissue"
						use:enhance={submitHandler}
						class="flex flex-col gap-4"
					>
						<label class="form-control w-full">
							<span class="label-text mb-1">シークレット</span>
							<input
								name="secret"
								type="text"
								required
								inputmode="numeric"
								class="input input-bordered w-full font-mono"
								placeholder="9桁の数字"
							>
						</label>
						<button type="submit" class="btn btn-secondary" disabled={loading}>
							{#if loading}
								<span class="loading loading-spinner"></span>
							{/if}
							再表示
						</button>
					</form>
				</div>
			</div>
		</section>
	</div>
{/if}
