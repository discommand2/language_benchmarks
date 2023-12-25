use std::sync::atomic::{AtomicUsize, Ordering};
use std::sync::Arc;
use ctrlc::set_handler;
use rustc_version::{version};
use num_format::{Locale, ToFormattedString};

fn main() {
    let count_loops = Arc::new(AtomicUsize::new(0));
    let count_loops_clone = Arc::clone(&count_loops);

    set_handler(move || {
        println!("Rust {} incremented {} times.",
                 version().unwrap(),
                 count_loops_clone.load(Ordering::SeqCst).to_formatted_string(&Locale::en));
        std::process::exit(0);
    }).expect("Error setting Ctrl-C handler");

    loop {
        count_loops.fetch_add(1, Ordering::SeqCst);
    }
}