use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use ctrlc::set_handler;

fn main() {
    let count_loops = Arc::new(AtomicUsize::new(0));
    let count_loops_clone = Arc::clone(&count_loops);

    set_handler(move || {
        println!("Rust {} executed the loop {} times before termination.",
                 std::env::consts::RUST_VERSION,
                 count_loops_clone.load(Ordering::Relaxed));
        std::process::exit(0);
    }).expect("Error setting Ctrl-C handler");

    loop {
        count_loops.fetch_add(1, Ordering::Relaxed);
    }
}