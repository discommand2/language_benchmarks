use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use signal_hook::{consts::SIGTERM, iterator::Signals};

fn main() {
    // Create a shared atomic counter
    let count_loops = Arc::new(AtomicUsize::new(0));

    // Clone the counter to be used in the signal handler
    let count_loops_clone = Arc::clone(&count_loops);

    // Set up the signal handler for SIGTERM
    let mut signals = Signals::new(&[SIGTERM]).expect("Error setting up signal handler");
    std::thread::spawn(move || {
        for _ in signals.forever() {
            println!("Rust executed the loop {} times before termination.",
                     count_loops_clone.load(Ordering::Relaxed));
            std::process::exit(0);
        }
    });

    // Loop indefinitely, incrementing the counter
    loop {
        count_loops.fetch_add(1, Ordering::Relaxed);
    }
}