def greedy_smart_cart(wishlist, budget):
    """
    Fungsi Algoritma Greedy untuk memaksimalkan jumlah barang
    berdasarkan budget yang tersedia.
    """
    
    # LANGKAH 1: SORTING (Inti Algoritma Greedy)
    # Urutkan barang dari harga termurah ke termahal (Ascending).
    # lambda x: x['harga'] artinya kita mengurutkan berdasarkan key 'harga'.
    wishlist_sorted = sorted(wishlist, key=lambda x: x['harga'])

    keranjang_final = []
    sisa_budget = budget
    total_belanja = 0

    print(f"=== MULAI PROSES GREEDY (Budget Awal: Rp {budget:,}) ===\n")

    # LANGKAH 2: SELEKSI (Iterasi)
    for barang in wishlist_sorted:
        nama_barang = barang['nama']
        harga_barang = barang['harga']

        # Cek Kelayakan: Apakah uang masih cukup untuk barang ini?
        if harga_barang <= sisa_budget:
            # Jika cukup, AMBIL
            keranjang_final.append(barang)
            sisa_budget -= harga_barang
            total_belanja += harga_barang
            print(f"[AMBIL]  {nama_barang:<15} | Harga: Rp {harga_barang:,} | Sisa: Rp {sisa_budget:,}")
        else:
            # Jika tidak cukup, LEWATI (Karena sudah diurutkan, barang berikutnya pasti lebih mahal, loop bisa stop atau lanjut cek)
            # Dalam greedy murni sorted, biasanya kita bisa stop di sini, tapi kita print untuk log.
            print(f"[SKIP]   {nama_barang:<15} | Harga: Rp {harga_barang:,} | (Saldo Kurang)")

    return keranjang_final, sisa_budget, total_belanja

# --- DATA UTAMA (Sesuai Studi Kasus) ---
saldo_dompet = 150000

daftar_wishlist = [
    {'nama': 'Jam Tangan', 'harga': 120000},
    {'nama': 'Kaos Polos', 'harga': 45000},
    {'nama': 'Topi', 'harga': 25000},
    {'nama': 'Stiker Pack', 'harga': 10000},
    {'nama': 'Casing HP', 'harga': 35000}
]

# --- EKSEKUSI PROGRAM ---
items_didapat, saldo_akhir, terpakai = greedy_smart_cart(daftar_wishlist, saldo_dompet)

# --- TAMPILKAN HASIL AKHIR ---
print("\n" + "="*40)
print("           HASIL AKHIR BELANJA           ")
print("="*40)
print(f"Jumlah Barang : {len(items_didapat)} Item")
print(f"Barang        : {', '.join([item['nama'] for item in items_didapat])}")
print(f"Total Belanja : Rp {terpakai:,}")
print(f"Sisa Saldo    : Rp {saldo_akhir:,}")
print("="*40)